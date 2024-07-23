$(document).ready(function() {
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.off('submit').submit();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const importBtn = document.getElementById('importBtn');
    const fileInput = document.getElementById('fileInput');
    const importForm = document.getElementById('importForm');

    importBtn.addEventListener('click', function() {
        fileInput.click();  
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            importForm.submit(); 
        }
    });
});
