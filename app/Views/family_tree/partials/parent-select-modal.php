<!-- views/family-tree/partials/parent-select-modal.php -->
<div class="modal fade" id="parentSelectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Father</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text"
                           class="form-control tree-search"
                           placeholder="Search family members..."
                           autofocus>
                </div>
                <div id="parentTreeView" class="family-tree-view overflow-auto">
                    <!-- Tree will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>