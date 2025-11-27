document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals
    initializeAllModals();

    // Initialize search functionality
    initializeSearch();

    // Initialize clear buttons
    initializeClearButtons();
});

function initializeAllModals() {
    ['parent', 'mother', 'spouse'].forEach(type => {
        const modal = document.querySelector(`#${type}SelectModal`);
        if (modal) {
            // Load tree when modal opens
            modal.addEventListener('show.bs.modal', function() {
                loadFamilyTree(type);
            });

            // Cleanup when modal closes
            modal.addEventListener('hidden.bs.modal', function() {
                cleanupModal();
            });
        }
    });
}

function initializeSearch() {
    document.querySelectorAll('.tree-search').forEach(searchInput => {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const modalId = this.closest('.modal').id;
            document.querySelectorAll(`#${modalId} .tree-item`).forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
}

function initializeClearButtons() {
    ['parent', 'mother', 'spouse'].forEach(type => {
        const clearButton = document.querySelector(`#clear${type.charAt(0).toUpperCase() + type.slice(1)}`);
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                document.querySelector(`#${type}_id`).value = '';
                document.querySelector(`#${type}_name`).value = '';
                if (type === 'parent') {
                    document.querySelector(`#parent_id`).value = '';
                }
            });
        }
    });
}

function loadFamilyTree(type) {
    const treeView = document.querySelector(`#${type}TreeView`);
    if (!treeView) return;

    treeView.innerHTML = '<div class="text-center"><i class="bi bi-hourglass-split"></i> Loading...</div>';

    fetch(`${baseUrl}/family-tree/get-tree/${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            treeView.innerHTML = html;
            initializeTreeBehavior(type);
        })
        .catch(error => {
            console.error('Error loading tree:', error);
            treeView.innerHTML = `
                <div class="alert alert-danger">
                    Error loading family tree. Please try again.
                    <br><small>Error details: ${error.message}</small>
                </div>`;
        });
}

function initializeTreeBehavior(type) {
    // Handle tree toggles
    document.querySelectorAll(`#${type}TreeView .tree-toggle`).forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const parentLi = this.closest('li');
            const childUl = parentLi.querySelector('ul');
            if (childUl) {
                const isVisible = childUl.style.display !== 'none';
                childUl.style.display = isVisible ? 'none' : '';
                this.querySelector('.bi').classList.toggle('bi-chevron-right');
                this.querySelector('.bi').classList.toggle('bi-chevron-down');
            }
        });
    });

    // Handle member selection
    document.querySelectorAll(`#${type}TreeView .select-family-member`).forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.dataset.id;
            const name = this.dataset.name;

            // Set the selected values
            if (type === 'parent') {
                document.querySelector('#parent_id').value = id;
                document.querySelector('#parent_name').value = name;
            } else {
                document.querySelector(`#${type}`).value = id;
                document.querySelector(`#${type}_name`).value = name;
            }

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.querySelector(`#${type}SelectModal`));
            if (modal) {
                modal.hide();
            }
        });
    });

    // Add hover effects
    document.querySelectorAll(`#${type}TreeView .tree-content`).forEach(content => {
        content.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        content.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
}

function cleanupModal() {
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
    document.body.style.paddingRight = '';
}

// Helper function to prevent selection of invalid relationships
function validateFamilySelection(type, selectedId) {
    const currentId = document.querySelector('#member_id')?.value;
    if (currentId === selectedId) {
        alert('A person cannot be their own ' + type);
        return false;
    }

    // Additional validation logic can be added here
    // For example, preventing circular relationships or age-based validations

    return true;
}

// Optional: Add validation before submission
document.querySelector('#familyMemberForm')?.addEventListener('submit', function(e) {
    const parentId = document.querySelector('#parent_id').value;
    const motherId = document.querySelector('#mother').value;
    const spouseId = document.querySelector('#spouse').value;
    const memberId = document.querySelector('#member_id')?.value;

    // Prevent self-references
    if (memberId) {
        if (parentId === memberId || motherId === memberId || spouseId === memberId) {
            e.preventDefault();
            alert('Invalid family relationship detected. A person cannot be their own parent, mother, or spouse.');
            return false;
        }
    }

    // Add any additional validation logic here

    return true;
});