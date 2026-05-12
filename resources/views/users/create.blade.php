<x-dashboard-layout>
   <main class="main-content add-new-artist-page" id="artist_create_update_form">
      <header class="content-header">
        <div style="display: flex; align-items: center; gap: 16px;">
          <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <div class="hamburger">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </button>
          <div class="flex flex-col gap-2">
              @if(isset($user)) 
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('users.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Users</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Edit User</span>
                </nav>
                <h1 class="page-title">Update User</h1>
                <p class="page-description">Please complete the fields below to create an accurate user.</p>
              @else
                <nav class="text-sm breadcrumbs flex items-center gap-1 mb-1 mt-1 edit_breadcrumb">
                  <a href="{{ route('users.index') }}" class="text-violet-700 hover:underline breadcrumb edit_breadcrumb_anchor_first">Users</a>
                  <span class="text-gray-500 mx-1 slash edit_breadcrumb_anchor_slash">/</span>
                  <span class="text-gray-500 breadcrumb edit_breadcrumb_anchor_name">Add User</span>
                </nav>
                <h1 class="page-title">Add New User</h1>
                <p class="page-description">Please complete the fields below to create an accurate project.</p>
              @endif
          </div>
        </div>
      </header>
      
      <div class="content-body">
        <div class="artist-update-form flex items-center space-x-2 mb-6 p-1">
          <form 
              action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
              method="POST" 
              class="flex flex-col w-full max-w-4xl items-start gap-12" 
              id="F_saveArtist"
          >
              @csrf
              
              {{-- If we are editing, we spoof the PUT method --}}
              @if(isset($user))
                  @method('PUT')
              @endif

          <section class="edit-artist-details flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto]">
            <header
              class="edit-artist-header flex flex-col w-full items-start align-center justify-center gap-1.5 px-3 py-1 relative flex-[0_0_auto]"
            >
              <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex-col items-start flex-1 grow flex relative">
                  <div class="items-center gap-4 self-stretch w-full flex-[0_0_auto] flex relative">
                    <div class="flex items-center gap-2 relative flex-1 self-stretch grow">
                      <h2
                        class="relative w-fit mt-[-1.00px] [font-family:'BR_Hendrix-SemiBold',Helvetica] text-lg tracking-[0] whitespace-nowrap"
                      >
                        USER DETAILS
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
            </header>
            <div class="flex-col items-start gap-8 self-stretch w-full flex-[0_0_auto] flex relative remove-extra-space-bottom">
                 <!-- Role Selection -->
            <div class="space-y-6 w-full mt-6">
                <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                    <label class="text-gray-600 font-medium">Role<span class="astric">*</span></label>
                    <div class="flex flex-col gap-2 w-full ermsg">
                        <div class="relative w-full">
                            <label for="role" class="portal-floating-label active">Role</label>
                            <select id="role" name="role" class="portal-input-field h-12" required title="Please select role">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ (old('role') ? old('role') : (isset($user) ? ($user->roles->first()->name ?? '') : '')) == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="space-y-6 w-full">
              <!-- Row 1 -->
              <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                <label class="text-gray-600 font-medium">Full Name<span class="astric">*</span></label>

                <div class="flex flex-col gap-2 w-full ermsg">
                  <div class="relative w-full">
                    <label for="name" class="portal-floating-label {{ old('name', $user->name ?? '') ? 'active' : '' }}">Full Name</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      required
                      aria-required="true"
                      maxlength="50"
                      value="{{ old('name', $user->name ?? '') }}"
                      title="Please enter full name"
                      class="portal-input-field"
                      data-state="{{ old('name', $user->name ?? '') ? 'filled' : 'placeholder' }}"
                    />
                  </div>
                </div>
              </div>
              
              <!-- Row 2: Email -->
              <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                <label class="text-gray-600 font-medium">Email<span class="astric">*</span></label>

                <div class="flex flex-col gap-2 w-full ermsg">
                  <div class="relative w-full">
                    <label for="email" class="portal-floating-label {{ old('email', $user->email ?? '') ? 'active' : '' }}">Email</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      required
                      aria-required="true"
                      maxlength="150"
                      value="{{ old('email', $user->email ?? '') }}"
                      title="Please enter email"
                      class="portal-input-field"
                      data-state="{{ old('email', $user->email ?? '') ? 'filled' : 'placeholder' }}"
                    />
                  </div>
                </div>
              </div>

              <!-- Row 3: Password -->
              <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                  <label class="text-gray-600 font-medium">Password @if(!isset($user))<span class="astric">*</span>@endif</label>
                  <div class="flex flex-col gap-2 w-full ermsg">
                      <div class="relative w-full">
                          <label for="password" class="portal-floating-label">Password</label>
                          <input type="password" id="password" name="password" class="portal-input-field" title="Please enter password" {{ !isset($user) ? 'required' : '' }}>
                      </div>
                  </div>
              </div>

              <!-- Row 4: Confirm Password -->
              <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                  <label class="text-gray-600 font-medium">Confirm Password @if(!isset($user))<span class="astric">*</span>@endif</label>
                  <div class="flex flex-col gap-2 w-full ermsg">
                      <div class="relative w-full">
                          <label for="password_confirmation" class="portal-floating-label">Confirm Password</label>
                          <input type="password" id="password_confirmation" name="password_confirmation" class="portal-input-field" title="Please confirm password" {{ !isset($user) ? 'required' : '' }}>
                      </div>
                  </div>
              </div>
            </div>
          </section>
        </div>
      </div>
        <footer class="artist-edit-footer right-2.5 bottom-0 h-16 flex bg-neutral-50 fixed admin-footer">
            <div class="artist-edit-footer-container flex flex-col gap-3 bg-neutral-50">
              <img
                class="artist-image-footer h-px -mt-px object-cover d-none"
                src="{{ asset('assets/svg/icons/line-40.svg') }}"
                alt=""
                aria-hidden="true"
              />
              <div class="artist-edit-footer-content flex h-10 relative items-center justify-between gap-14">
                <p
                  class="artist-edit-status-message relative flex-1 [font-family:'BR_Hendrix-Regular',Helvetica] font-normal text-transparent text-sm tracking-[0] leading-[14px]"
                  role="status"
                  aria-live="polite"
                  style="display: none;"
                >
                  <span class="text-[#1c1b1b] leading-5">Not ready to submit for review.</span>
                  <span class="[font-family:'BR_Hendrix-Medium',Helvetica] font-medium text-[#1c1b1b] leading-[0.1px]"
                    >&nbsp;</span
                  >
                  <span class="text-[#ff5a5d] leading-5"></span>
                </p>
                <input type="hidden" name="submit_action" id="submit_action">
                <div class="artist-edit-buttons-container inline-flex items-center gap-4 relative flex-[0_0_auto]">
                    <button  
                      type="submit" name="submit" value="in-review"
                      class="artist-edit-approve-btn portal-btn-primary-small directSubmit"  
                      id="saveArtist"
                    >
                      @if(isset($user)) 
                        Update
                      @else
                        Save
                      @endif
                    </button>
                </div>
              </div>
            </div>
        </footer>
        </form>
    </main>
@section('uniquePageScript')
@endsection
</x-dashboard-layout>