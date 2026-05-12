<div id="logCallModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 bg-opacity-50 transition-opacity backdrop-blur-sm" id="logCallModalBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                
                <div class="bg-[#84cc16] px-4 py-3 sm:px-6 flex justify-between items-center model-title-bg">
                    <h3 class="text-base font-semibold leading-6 text-white capitalize" id="logModalTitle">Log Activity</h3>
                    <button type="button" class="text-white hover:text-gray-100 focus:outline-none" id="closeLogCallModalX">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('leads.logs.store') }}" method="POST" id="F_submitLogCall">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <input type="hidden" name="log_type" id="log_type_input">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 space-y-4">

                        <div id="logDurationWrapper" class="hidden">
                            <label for="log_duration" class="block text-sm font-medium leading-6 text-gray-900">Duration</label>
                            <div class="mt-1 ermsg">
                                <select id="log_duration" name="duration" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50">
                                    <option value="">Select Option</option>
                                    
                                    {{-- Loop through the variable passed from the Controller --}}
                                    @foreach($durations as $minutes => $label)
                                        <option value="{{ $minutes }}">{{ $label }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="call_date" class="block text-sm font-medium leading-6 text-gray-900">Date</label>
                                <div class="mt-1 relative ermsg">
                                    <input type="text" name="log_date" id="call_date" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50 datepicker" placeholder="Select Date" required>
                                </div>
                            </div>
                            <div>
                                <label for="call_time" class="block text-sm font-medium leading-6 text-gray-900">Time</label>
                                <div class="mt-1 relative ermsg">
                                    <input type="text" name="log_time" id="call_time" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50 timepicker" placeholder="Select Time" required>
                                </div>
                            </div>
                        </div>

                        <div id="logCallOutcomeWrapper">
                            <label for="call_outcome" class="block text-sm font-medium leading-6 text-gray-900">Call Outcome</label>
                            <div class="mt-1 ermsg">
                                <select id="call_outcome" name="outcome" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50">
                                    <option value="">Select Option</option>
                                    <option value="connected">Connected</option>
                                    <option value="no_answer">No Answer</option>
                                    <option value="busy">Busy</option>
                                    <option value="wrong_number">Wrong Number</option>
                                </select>
                            </div>
                        </div>

                        <div id="logMeetingOutcomeWrapper" class="hidden">
                            <label for="meeting_outcome" class="block text-sm font-medium leading-6 text-gray-900">Meeting Outcome</label>
                            <div class="mt-1 ermsg">
                                <select id="meeting_outcome" name="meeting_outcome" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50">
                                    <option value="">Select Option</option>
                                    <option value="interested">Interested</option>
                                    <option value="not_interested">Not Interested</option>
                                    <option value="rescheduled">Rescheduled</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="call_description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                            <div class="mt-1 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-[#84cc16]">
                                <textarea name="description" id="call_description" rows="4" class="block w-full border-0 bg-transparent py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 resize-none summernote" placeholder="Paragraph"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="call_status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                            <div class="mt-1 ermsg">
                                <select id="call_status" name="status" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50">
                                @php 
                                    $currentStatus = old('status', $lead->status ?? 'new');
                                    $statuses = ['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'lost' => 'Lost', 'closed' => 'Closed'];
                                @endphp
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $currentStatus == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="lead_type" class="block text-sm font-medium leading-6 text-gray-900">Lead Type</label>
                            <div class="mt-1 ermsg">
                                <select id="lead_type" name="lead_type" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-[#84cc16] sm:text-sm sm:leading-6 bg-gray-50">
                                    <option value="">Choose</option>
                                    <option value="hot">Hot</option>
                                    <option value="cold">Cold</option>
                                    <option value="warm">Warm</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 py-2">
                            <input type="checkbox" id="call_with_task" name="with_task" class="h-4 w-4 rounded border-gray-300 text-[#84cc16] focus:ring-[#84cc16]">
                            <label for="call_with_task" class="text-sm font-medium text-gray-700">With Task</label>
                        </div>

                        <div id="logCallTaskFields" class="space-y-4 hidden border-t border-gray-100 pt-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Task Date</label>
                                    <div class="mt-1 relative ermsg">
                                        <input type="text" name="task_date" id="call_task_date" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm bg-gray-50 datepicker" placeholder="Select Date">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Task Time</label>
                                    <div class="mt-1 relative ermsg">
                                        <input type="text" name="task_time" id="call_task_time" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm bg-gray-50 timepicker" placeholder="Select Time">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Task Type</label>
                                <div class="mt-1 ermsg">
                                    <select id="call_task_type" name="task_type" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm bg-gray-50">
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
                        <button type="submit" class="mt-3 w-full justify-center rounded-md bg-[#84cc16] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-600 sm:w-auto directSubmit" id="submitLogCall">Save</button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" id="closeLogCallModalBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>