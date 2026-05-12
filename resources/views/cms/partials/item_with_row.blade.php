<div class="items-block" data-index="{{ $i }}">


       <div id="press-container">

              <fieldset class="press-item flex flex-col w-[626px] items-start gap-2.5 p-4 relative rounded border border-solid border-[#dddddd]">

                     <legend class="relative flex items-center justify-between self-stretch">
                            <span>Item {{ (int)$i + 1 }}</span>
                            <button type="button" class="removeItem  text-red-500 text-sm cursor-pointer ml-2" data-index="2" fdprocessedid="bg1j">
                                   ❌
                            </button>

                     </legend>
                     @if(in_array('image', $fields))

                     <div class="flex items-start gap-2.5 w-full">
                            <div class="h-[52px] flex-1 shadow-shadow-XS flex flex-col">
                                   <div class="flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                          <div class="flex flex-col flex-1">
                                                 <input type="hidden"
                                                        name="services[{{ $i }}][old_image]"
                                                        value="{{ $service['image'] ?? '' }}">
                                                 <input type="file"
                                                        name="services[{{ $i }}][image]"
                                                        accept="image/png,image/jpeg,image/webp"
                                                        class="w-full border-none">

                                          </div>


                                   </div>

                            </div>
                            @if(!empty($service['image']))
                            <img src="{{ azure_url($service['image']) }}"
                                   alt="{{ $service['alt'] ?? '' }}"
                                   class="w-32 h-32 object-cover rounded mb-3">
                            @endif
                     </div>
                     @endif

                     @if(in_array('alt', $fields))

                     <div class="flex items-start gap-2.5 w-full">
                            <div class="h-[52px] flex-1 shadow-shadow-XS flex flex-col">
                                   <div class="flex items-center gap-2 px-3 py-1.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                          <div class="flex flex-col flex-1">
                                                 <input type="text" name="services[{{ $i }}][alt]" value="{{ $service['alt'] ?? '' }}" class="w-full text-gray-700 placeholder-gray-400 outline-none" placeholder="Alt Text" title="Please enter alt text" fdprocessedid="w72jmp">
                                          </div>
                                   </div>
                            </div>
                     </div>
                     @endif

                     @if(in_array('title', $fields))

                     <div class="w-full shadow-shadow-XS flex flex-col">
                            <div class="flex items-center gap-2 px-3 py-3.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                   <div class="flex flex-col flex-1">
                                          <input type="text" name="services[{{ $i }}][title]" value="{{ $service['title'] ?? '' }}" class="w-full text-gray-700 placeholder-gray-400 outline-none" placeholder="Title" title="Please enter alt text" fdprocessedid="w72jmp">
                                   </div>
                            </div>
                     </div>
                     @endif
                     @if(in_array('description', $fields))

                     <div class="w-full shadow-shadow-XS flex flex-col">
                            <div class="flex items-center gap-2 px-3 py-3.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                   <div class="flex flex-col flex-1">
                                          <input type="text" name="services[{{ $i }}][description]" value="{{ $service['description'] ?? '' }}" class="w-full text-gray-700 placeholder-gray-400 outline-none" placeholder="Description" title="Description" fdprocessedid="w72jmp">
                                   </div>
                            </div>
                     </div>
                     @endif
                     @if(in_array('order', $fields))
                     <div class="w-full shadow-shadow-XS flex flex-col">
                            <div class="flex items-center gap-2 px-3 py-3.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                   <div class="flex flex-col flex-1">
                                          <input type="text" value="{{ $service['order'] ?? '' }}" name="services[{{ $i }}][order]" class="w-full text-gray-700 placeholder-gray-400 outline-none" placeholder="Order" title="Order" fdprocessedid="w72jmp">
                                   </div>
                            </div>
                     </div>
                     @endif
                       @if(in_array('type', $fields))
                     <div class="w-full shadow-shadow-XS flex flex-col">
                            <div class="flex items-center gap-2 px-3 py-3.5 w-full bg-white rounded border border-[#dddddd] ermsg">
                                   <div class="flex flex-col flex-1">
                                          <select name="services[{{ $i }}][type]" class="w-full">
                                                 <option value="left" {{ ($service['type'] ?? '') == 'left' ? 'selected' : '' }}>
                                                        Left
                                                 </option>
                                                 <option value="right" {{ ($service['type'] ?? '') == 'right' ? 'selected' : '' }}>
                                                        Right
                                                 </option>
                                          </select>

                                   </div>
                            </div>
                     </div>
                     @endif

                     <div class="w-full shadow-shadow-XS flex flex-col">
                            <div class="bullet-wrapper" data-service="{{ $i }}">

                                   @php
                                   $bullets = $service['bullets'] ?? [];
                                   @endphp

                                   @foreach($bullets as $bIndex => $bullet)
                                   <div class="flex items-center gap-2 mb-2 bullet-item">
                                          <input type="text"
                                                 name="services[{{ $i }}][bullets][{{ $bIndex }}]"
                                                 value="{{ $bullet }}"
                                                 class="border w-full px-2 py-1 rounded"
                                                 placeholder="Enter bullet point">

                                          <button type="button" class="removeBullet  text-red-500 text-sm cursor-pointer ml-2" data-index="2" fdprocessedid="bg1j">
                                                 ❌
                                          </button>
                                   </div>
                                   @endforeach

                                   <!-- Empty template for new bullets -->
                                   <div class="flex items-center gap-2 mb-2 bullet-item hidden template">
                                          <input type="text"
                                                 class="border w-full px-2 py-1 rounded"
                                                 placeholder="Enter bullet point">


                                          <button type="button" class="removeBullet  text-red-500 text-sm cursor-pointer ml-2" data-index="2" fdprocessedid="bg1j">
                                                 ❌
                                          </button>
                                   </div>
                                   @if(in_array('item_block', $fields))
                                   
                                   <button type="button"
                                          class="addBullet bg-gray-800 text-white px-3 py-1 rounded mt-2"
                                          data-service="{{ $i }}">
                                          + Add Bullet
                                   </button>
                                   @endif
                            </div>
                     </div>
              </fieldset>
       </div>
       <!-- Alt Text -->


       <!-- Service Title -->

       <!-- Description -->

       <!-- Order -->

</div>