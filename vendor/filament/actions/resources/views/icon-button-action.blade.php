<x-filament-actions::action
    :action="$action"
    :badge="$getBadge()"
    :badge-color="$getBadgeColor()"
    dynamic-component="filament::icon-button"
    :label="$getLabel()"
    :size="$getSize()"
    class="fi-ac-icon-btn-action"
/>
