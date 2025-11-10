<div>
    @if($viewToRender == 'dashboard.admin-dashboard')
        
        <livewire:dashboard.admin-dashboard />
        
    @elseif($viewToRender == 'dashboard.teacher-dashboard')
    
        <livewire:dashboard.teacher-dashboard />
        
    @elseif($viewToRender == 'dashboard.student-dashboard')
    
        <livewire:dashboard.student-dashboard />
        
    @endif
</div>