@props(['action', 'data'])


   
  <div class="modal-dialog">
    <form id="form-action" action="{{ $action }}" method="POST">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <div class="col-12">
      <div class="mb-3">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-success" value="success">
          <label class="form-check-label" for="category-success">Success</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-danger" value="danger">
          <label class="form-check-label" for="category-danger">Danger</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-warning" value="warning">
          <label class="form-check-label" for="category-warning">Warning</label>
        </div>
      </div>
    </div>
   </form>
  </div>