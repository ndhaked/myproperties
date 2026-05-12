
<div class="modal fade" id="tokenErrorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Session Expired</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Your password reset token is invalid or has expired. <br>
        Please restart the password reset process.
      </div>
      <div class="modal-footer">
        <a href="{{ route('password.request') }}" class="btn btn-primary">Restart Reset Flow</a>
      </div>
    </div>
  </div>
</div>
