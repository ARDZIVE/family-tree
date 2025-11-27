<style>
    .family-tree-view {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .tree-item {
        list-style-type: none;
        margin: 5px 0;
    }

    .tree-toggle {
        cursor: pointer;
        color: #0d6efd;
        padding: 2px 5px;
        border-radius: 3px;
    }

    .tree-toggle:hover {
        background-color: #e9ecef;
    }

    .tree-content {
        padding: 8px;
        margin: 2px 0;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .tree-content:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .select-family-member {
        color: #0d6efd;
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .select-family-member:hover {
        background-color: #e9ecef;
        text-decoration: none;
    }

    .tree-member-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-left: 8px;
    }

    .family-tree-view ul {
        margin-bottom: 0;
        padding-left: 20px;
    }

    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }

    .tree-search {
        position: sticky;
        top: 0;
        z-index: 1;
        background: white;
    }
</style>