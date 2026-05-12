<div id="noteModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 bg-opacity-50 transition-opacity backdrop-blur-sm" id="noteModalBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                
                <div class="bg-[#84cc16] px-4 py-3 sm:px-6 flex justify-between items-center model-title-bg">
                    <h3 class="text-base font-semibold leading-6 text-white capitalize" id="modal-title">New Note</h3>
                    <button type="button" class="text-white hover:text-gray-100 focus:outline-none" id="closeNoteModalX">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('leads.notes.store') }}" method="POST" id="F_submitNote">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 space-y-4">
                        
                        <div class="ermsg">
                            <label for="note_description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                            <div class="mt-1 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-[#84cc16]">
                                <textarea required name="description" id="note_description" rows="4" class="block w-full border-0 bg-transparent py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 resize-none summernote" placeholder="Paragraph" title="Please enter note"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="with_task" name="with_task" class="h-4 w-4 rounded border-gray-300 text-[#84cc16] focus:ring-[#84cc16]">
                                <label for="with_task" class="text-sm font-medium text-gray-700">With Task</label>
                            </div>
                            <div class="flex items-center gap-2" style="display: none;">
                                <input type="checkbox" id="notify_admin" name="notify_admin" class="h-4 w-4 rounded border-gray-300 text-[#84cc16] focus:ring-[#84cc16]">
                                <label for="notify_admin" class="text-sm font-medium text-gray-700">Send Notification To Admin</label>
                            </div>
                        </div>

                        <div id="noteTaskFields" class="space-y-4 hidden">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="note_date" class="block text-sm font-medium leading-6 text-gray-900">Date</label>
                                    <div class="mt-1 relative ermsg">
                                        <input type="text" name="task_date" id="note_date" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50 datepicker" placeholder="Select Date" title="Please select date">
                                    </div>
                                </div>
                                <div>
                                    <label for="note_time" class="block text-sm font-medium leading-6 text-gray-900">Time</label>
                                    <div class="mt-1 relative ermsg">
                                        <input type="text" name="task_time" id="note_time" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50 timepicker" placeholder="Select Time" title="Please select time">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="note_task_type" class="block text-sm font-medium leading-6 text-gray-900">Type</label>
                                <div class="mt-1 ermsg">
                                    <select id="note_task_type" name="task_type" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50" title="Please select type">
                                        <option value="">Select Option</option>
                                        <option value="email">Email</option>
                                        <option value="call">Call</option>
                                        <option value="whatsapp">Whatsapp</option>
                                        <option value="meeting">Meeting</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:px-6 gap-2">
                        <button type="submit" class="mt-3 w-full justify-center rounded-md bg-[#84cc16] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-600 sm:w-auto directSubmit" id="submitNote">Save</button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" id="closeNoteModalBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>