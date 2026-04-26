{{-- ══════════════════════════════════════════════════════════
     FORGOT PASSWORD
══════════════════════════════════════════════════════════ --}}
<div id="at-forgot" class="hidden px-6 py-6">
  <button onclick="authTab('login')"
          class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-700 mb-4 transition-colors">
    <i data-lucide="arrow-left" style="width:11px;height:11px"></i> Back to sign in
  </button>
  <p class="text-xs text-slate-500 mb-5">
    Enter your email and we'll send a password reset link to your inbox.
  </p>
  <form id="f-forgot" class="space-y-4" onsubmit="doForgot(event)">
    @csrf
    <div>
      <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
      <div class="relative">
        <i data-lucide="mail"
           style="width:14px;height:14px;position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
        <input type="email" name="email" required placeholder="you@email.com"
               class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                      focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
      </div>
    </div>
    <button type="submit"
            class="w-full flex items-center justify-center gap-2 py-2.5 bg-primary-600 hover:bg-primary-700
                   text-white text-sm font-semibold rounded-xl transition-colors">
      <i data-lucide="send" style="width:14px;height:14px"></i> Send Reset Link
    </button>
  </form>
</div>