<div class="col-sm-9" id="tree">
    <?= $this->requirePart('tree', $data) ?>
</div>
<div class="col-sm-6">
    <form action="/add" class="form-group" id="createRoot" method="post">
        <input type="hidden" name="parent_id" value="0">
        <input type="hidden" name="title" value="Root">
        <button class="btn btn-primary" type="submit">Create Root</button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This is very dangerous, you shouldn't do it! Are you really really sure?
            </div>
            <div class="modal-footer">
                <span class="text-danger flex-fill h1">30</span>
                <button type="button" class="btn btn-primary" id="sendConfirmation" data-dismiss="modal">Yes I am</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">none</button>
            </div>
        </div>
    </div>
</div>