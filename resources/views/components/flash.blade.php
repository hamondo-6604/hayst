@php
  $flashes = [
    'success' => ['bg-emerald-500', 'check-circle'],
    'error'   => ['bg-red-500',     'x-circle'],
    'warning' => ['bg-amber-500',   'alert-triangle'],
    'info'    => ['bg-blue-500',    'info'],
  ];
@endphp

@foreach($flashes as $type => [$bg, $icon])
  @if(session($type))
    <div id="flash-{{ $type }}"
         class="toast-enter fixed top-20 right-4 z-[9998] flex items-center gap-3 {{ $bg }} text-white text-sm font-medium px-4 py-3 rounded-2xl shadow-xl max-w-sm">
      <i data-lucide="{{ $icon }}" style="width:16px;height:16px;flex-shrink:0"></i>
      <span>{{ session($type) }}</span>
      <button onclick="document.getElementById('flash-{{ $type }}').remove()" class="ml-auto opacity-80 hover:opacity-100">
        <i data-lucide="x" style="width:13px;height:13px"></i>
      </button>
    </div>
    <script>setTimeout(()=>{const el=document.getElementById('flash-{{ $type }}');if(el){el.classList.replace('toast-enter','toast-leave');setTimeout(()=>el?.remove(),280);}},3500);</script>
  @endif
@endforeach