{{-- ══════════════════════════════════════════════════════════
     SIGN IN
══════════════════════════════════════════════════════════ --}}
<div id="at-login" class="px-6 py-6">
  <p class="text-xs text-slate-500 mb-5">Welcome back! Enter your credentials to continue.</p>

  <form id="f-login" class="space-y-4" onsubmit="doLogin(event)">
    @csrf

    {{-- Email --}}
    <div>
      <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
      <div class="relative">
        <i data-lucide="mail"
           style="width:14px;height:14px;position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
        <input type="email" name="email" id="input-login-email" autocomplete="email" placeholder="you@email.com"
               class="log-input w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                      focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
      </div>
      <p class="error-msg text-xs text-red-500 mt-1 hidden" id="err-login-email"></p>
    </div>

    {{-- Password --}}
    <div>
      <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
      <div class="relative">
        <i data-lucide="lock"
           style="width:14px;height:14px;position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
        <input type="password" id="input-login-password" name="password" autocomplete="current-password"
               placeholder="••••••••"
               class="log-input w-full pl-9 pr-10 py-2.5 text-sm border border-slate-200 rounded-xl
                      focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
        <button type="button" onclick="togglePwd('input-login-password')"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
          <i data-lucide="eye" style="width:14px;height:14px"></i>
        </button>
      </div>
      <p class="error-msg text-xs text-red-500 mt-1 hidden" id="err-login-password"></p>
    </div>

    {{-- Remember + Forgot --}}
    <div class="flex items-center justify-between">
      <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer select-none">
        <input type="checkbox" name="remember"
               class="w-3.5 h-3.5 rounded border-slate-300 accent-primary-600">
        Remember me
      </label>
      <button type="button" onclick="authTab('forgot')"
              class="text-xs text-primary-600 font-semibold hover:text-primary-700 transition-colors">
        Forgot password?
      </button>
    </div>

    <button type="submit" id="btn-login"
            class="w-full flex items-center justify-center gap-2 py-2.5 bg-primary-600 hover:bg-primary-700
                   text-white text-sm font-semibold rounded-xl transition-colors">
      <i data-lucide="log-in" style="width:14px;height:14px"></i> Sign In
    </button>
  </form>

  <p class="text-center text-xs text-slate-500 mt-4">
    No account?
    <button onclick="authTab('register')"
            class="text-primary-600 font-semibold hover:text-primary-700">
      Create one free
    </button>
  </p>
</div>