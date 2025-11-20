<?php

namespace App\Livewire\Pages\Treasury;

use App\Models\CashSession;
use App\Models\Institution;
use App\Models\PaymentConcept;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherItem;
use App\Models\VoucherSeries;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class TupaPointOfSale extends Component
{
    // --- ESTADO DE CAJA ---
    public ?CashSession $activeSession = null;

    // --- BÚSQUEDA DE CLIENTE ---
    public $userSearch = '';
    public Collection $usersFound;
    public ?User $selectedUser = null;

    // --- REGISTRO DE USUARIO EXTERNO ---
    public $isExternalUserModalOpen = false;
    public $new_user_name = '';
    public $new_user_email = ''; // Opcional
    public $new_user_dni = '';

    // --- CARRITO DE COMPRAS ---
    public $conceptSearch = '';
    public Collection $conceptsFound;
    public $cart = []; // Array de items ['id', 'description', 'price', 'quantity', 'subtotal']
    public $totalAmount = 0.00;

    // --- PAGO ---
    public $payment_method = 'Efectivo';
    public $voucher_type = 'recibo'; // 'boleta', 'recibo'
    public $transaction_code = '';
    public $observations = '';

    public function mount()
    {
        $this->checkActiveSession();
        $this->usersFound = collect();
        $this->conceptsFound = collect();
        $this->cart = [];
    }

    public function checkActiveSession()
    {
        $this->activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();
    }

    // --- BÚSQUEDA DE USUARIOS ---
    public function updatedUserSearch($value)
    {
        if (strlen($value) < 3) {
            $this->usersFound = collect();
            return;
        }
        $this->usersFound = User::where('name', 'like', '%' . $value . '%')
            ->orWhere('document_number', 'like', '%' . $value . '%') // Buscar por DNI
            ->take(5)
            ->get();
    }

    public function selectUser(User $user)
    {
        $this->selectedUser = $user;
        $this->userSearch = $user->name;
        $this->usersFound = collect();
    }

    // --- USUARIO EXTERNO ---
    public function openExternalUserModal()
    {
        $this->reset('new_user_name', 'new_user_email', 'new_user_dni');
        $this->isExternalUserModalOpen = true;
    }

    public function saveExternalUser()
    {
        $this->validate([
            'new_user_name' => 'required|string|max:255',
            'new_user_dni' => 'required|string|max:20|unique:users,document_number',
            'new_user_email' => 'nullable|email|unique:users,email',
        ]);

        // Crear usuario "ficticio" para el recibo
        $user = User::create([
            'name' => $this->new_user_name,
            'email' => $this->new_user_email ?: 'externo_' . $this->new_user_dni . '@system.local',
            'document_number' => $this->new_user_dni,
            'password' => Hash::make($this->new_user_dni), // Contraseña por defecto
            'user_type' => 'applicant', // Usamos un tipo base o creamos uno 'external'
            // Ojo: Si implementaste Roles, aquí deberías asignar $user->assignRole('Externo');
        ]);

        // Si usas Spatie:
        $user->assignRole('Externo'); // Asegúrate de que este rol exista en el Seeder

        $this->selectUser($user);
        $this->isExternalUserModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Usuario registrado']);
    }

    // --- BÚSQUEDA DE CONCEPTOS (TUPA) ---
    public function updatedConceptSearch($value)
    {
        if (strlen($value) < 3) {
            $this->conceptsFound = collect();
            return;
        }
        $this->conceptsFound = PaymentConcept::where('status', 'active')
            ->where(function ($q) use ($value) {
                $q->where('description', 'like', '%' . $value . '%')
                    ->orWhere('code', 'like', '%' . $value . '%');
            })
            ->take(10)
            ->get();
    }

    public function addToCart(PaymentConcept $concept)
    {
        // Verificar si ya está en el carrito
        foreach ($this->cart as $key => $item) {
            if ($item['id'] == $concept->id) {
                $this->cart[$key]['quantity']++;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
                $this->calculateTotal();
                $this->conceptSearch = '';
                $this->conceptsFound = collect();
                return;
            }
        }

        // Añadir nuevo
        $this->cart[] = [
            'id' => $concept->id,
            'description' => $concept->description,
            'price' => $concept->amount,
            'quantity' => 1,
            'subtotal' => $concept->amount,
        ];

        $this->calculateTotal();
        $this->conceptSearch = '';
        $this->conceptsFound = collect();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Reindexar
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = collect($this->cart)->sum('subtotal');
    }

    // --- PROCESAR PAGO ---
    public function processPayment()
    {
        $this->validate([
            'selectedUser' => 'required',
            'cart' => 'required|array|min:1',
            'voucher_type' => 'required',
            'payment_method' => 'required',
        ]);

        if (!$this->activeSession) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Caja Cerrada', 'text' => 'Debe abrir caja antes de cobrar.']);
            return;
        }

        try {
            DB::transaction(function () {
                $institution = Institution::first();

                // 1. Obtener Correlativo (Bloqueo pesimista para evitar duplicados)
                $series = VoucherSeries::where('institution_id', $institution->id)
                    ->where('voucher_type', $this->voucher_type)
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if (!$series) {
                    throw new \Exception("No hay una serie configurada para {$this->voucher_type}. Contacte al administrador.");
                }

                $nextNumber = $series->current_number + 1;
                $series->update(['current_number' => $nextNumber]);

                // 2. Crear el Voucher
                $voucher = Voucher::create([
                    'cash_session_id' => $this->activeSession->id,
                    'issuer_id' => Auth::id(),
                    'client_id' => $this->selectedUser->id,
                    'voucher_type' => $this->voucher_type,
                    'series' => $series->series,
                    'number' => $nextNumber,
                    'total_amount' => $this->totalAmount,
                    'payment_method' => $this->payment_method,
                    'transaction_code' => $this->transaction_code,
                    'observations' => $this->observations,
                    'status' => 'issued',
                    'issued_at' => now(),
                ]);

                // 3. Crear los Items
                foreach ($this->cart as $item) {
                    VoucherItem::create([
                        'voucher_id' => $voucher->id,
                        'payment_concept_id' => $item['id'],
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['subtotal'],
                    ]);
                }

                // 4. Descargar PDF
                $this->dispatch('open-pdf', url: route('treasury.voucher.download', $voucher->id));

                // 5. Resetear formulario
                $this->reset('selectedUser', 'userSearch', 'cart', 'totalAmount', 'transaction_code', 'observations');
                $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Venta Exitosa!', 'text' => 'El comprobante se ha generado.']);
            });
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.treasury.tupa-point-of-sale');
    }
}
