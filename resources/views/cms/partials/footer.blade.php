           <!-- <footer class="right-2.5 bottom-0 h-16 flex">
            <div class="w-[1142px] h-[64.0px] flex flex-col gap-3 bg-neutral-50">
              <img
                class="w-[1142px] h-px -mt-px object-cover"
                src="{{ asset('assets/svg/icons/line-40.svg') }}"
                alt=""
                aria-hidden="true"
              />
              <div class="flex ml-6 w-[1094px] h-10 relative items-center justify-end gap-14">
                <input type="hidden" name="submit_action" id="submit_action">
                <div class="inline-flex items-center gap-4 relative flex-[0_0_auto]">
                   <button
                    id="cmsForm"
                    type="submit" name="submit" value="save"
                    class="directSubmit all-[unset] box-border inline-flex h-10 flex-[0_0_auto] border border-solid border-[#505050] items-center justify-center gap-2 px-6 py-2.5 relative rounded-[999px] cursor-pointer directSubmit" id="saveArtist1"
                  >
                    <span
                      class="mt-[-1.00px] text-[#1c1b1b] relative flex items-center justify-center w-fit font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]"
                    >
                      Save
                    </span>
                  </button>
                  
                  
                </div>
              </div>
            </div>
          </footer> -->

<footer class="artist-edit-footer right-8 bottom-0 h-16 flex bg-neutral-50 fixed admin-footer">
      <div class="artist-edit-footer-container flex flex-col gap-3 bg-neutral-50">
        <img
            class="artist-image-footer h-px -mt-px object-cover d-none"
            src="{{ asset('assets/svg/icons/line-40.svg') }}"
            alt=""
            aria-hidden="true"
        />
        <div class="artist-edit-footer-content flex ml-8 h-10 relative items-center justify-end gap-14">
            <input
                type="hidden"
                name="submit_action"
                id="submit_action"
            />
            <div class="artist-edit-buttons-container inline-flex items-center gap-4 relative flex-[0_0_auto]">
                <button
                    id="cmsForm"
                    type="submit" name="submit" value="save"
                    class="artist-edit-save-btn portal-btn-secondary-small directSubmit">
                  Save  
                </button>
            </div>
        </div>
    </div>
</footer>