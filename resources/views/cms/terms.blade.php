<x-dashboard-layout>
  <main class="main-content cms-management-page">
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
          <h1 class="page-title">Terms and Condition</h1>
          <!-- <p>Please complete the fields below to create an accurate artist profile. Make sure all submitted information is accurate and properly formatted (including capitalization). Once submitted, any future edits will be reviewed and approved by our team.</p> -->
        </div>
      </div>
    </header>

    <div class="content-body">
      <div class="flex items-center space-x-2 overflow-x-auto mb-6 p-1 cms-form-wrapper">
        <form action="{{ route('cms.terms-save') }}" method="POST"
          class="flex flex-col w-[908px] items-start gap-12 top-[162px] left-[312px] cms-manage-form" id='F_saveArtist'>
          @csrf
          @if(isset($artist))
            @method('PUT')
          @endif
          <section class="flex flex-col items-start gap-5 relative self-stretch w-full flex-[0_0_auto] terms-wrapper">
            <!-- <header
              class="flex flex-col w-[908px] items-start justify-center gap-1.5 px-3 py-1 relative flex-[0_0_auto] bg-[#05f9e2] rounded-md">
              <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex-col items-start flex-1 grow flex relative">
                  <div class="items-center gap-4 self-stretch w-full flex-[0_0_auto] flex relative">

                  </div>
                </div>
              </div>
            </header> -->

            <label for="email_address"
              class="w-[250px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-base-medium-font-size)] leading-[var(--typography-paragraph-base-medium-line-height)] relative mt-[-1.00px] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
              Content
            </label>
            <!-- Quill Editor Container -->
            <div id="editor" style="height: 200px; width:540px;">{!! old('description', $data->description ?? '') !!}
              {!! old('content_html', $content ?? '') !!}
            </div>

            <!-- Hidden input to store HTML -->
            <!-- <input type="hidden" name="content_html" id="content_html">
            <button>Save</button> -->
          </section>
          @include('cms.partials.footer')
        </form>
      </div>
    </div>

  </main>

  @section('uniquePageScript')
  @endsection
  @section('script')
    <script>
      // Initialize Quill
      var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'header': [1, 2, 3, false] }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            [{ 'color': [] }, { 'background': [] }]
          ]
        }
      });

      // Sync Quill HTML to hidden input on form submit
      document.getElementById('F_saveArtist').addEventListener('submit', function () {
        document.getElementById('content_html').value = quill.root.innerHTML;
      });
    </script>
  @endsection
</x-dashboard-layout>