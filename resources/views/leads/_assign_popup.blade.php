<div id="assignLeadsModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeAssignModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                
                <div class="bg-[#84cc16] px-4 py-3 sm:px-6 flex justify-between items-center model-title-bg">
                    <h3 class="text-base font-semibold leading-6 text-white capitalize" id="modal-title">Assign Leads</h3>
                    <button type="button" class="text-white hover:text-gray-100 focus:outline-none" onclick="closeAssignModal()">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 space-y-4">
                    <div class="ermsg">
                        <label for="assign_agent_id" class="block text-sm font-medium leading-6 text-gray-900">Users</label>
                        <div class="mt-1 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-[#84cc16]">
                            <select name="assign_agent_id" id="assign_agent_id" class="block w-full border-0 bg-transparent py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
                                <option value="">Select User</option>
                                @if(isset($agents))
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:px-6 gap-2">
                    <button type="button" id="submitAssignLeads" class="mt-3 w-full justify-center rounded-md bg-[#84cc16] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-600 sm:w-auto" data-leadassignroute="{{ route('leads.assign') }}">Assign</button>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeAssignModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
