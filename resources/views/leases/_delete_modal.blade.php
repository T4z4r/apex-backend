<!-- Delete Lease Modal -->
<div class="modal fade" id="deleteLeaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      @csrf @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title">Delete Lease</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this lease <strong class="delete-item-name"></strong>?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" type="submit">Delete</button>
      </div>
    </form>
  </div>
</div>