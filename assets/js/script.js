// Main JavaScript functions for the website

// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

// Cart functionality
function updateCartCount(count) {
    $('#cartCount').text(count);
}

// Add to cart animation
$('.add-to-cart').click(function() {
    const bookId = $(this).data('book-id');
    const button = $(this);
    
    button.html('<i class="fas fa-spinner fa-spin"></i> شامل ہو رہا ہے');
    
    setTimeout(function() {
        button.html('<i class="fas fa-cart-plus"></i> کارٹ میں شامل کریں');
    }, 1000);
});

// Quantity buttons in cart
$('body').on('click', '.increase-quantity', function() {
    const input = $(this).siblings('.quantity-input');
    let value = parseInt(input.val());
    input.val(value + 1);
});

$('body').on('click', '.decrease-quantity', function() {
    const input = $(this).siblings('.quantity-input');
    let value = parseInt(input.val());
    if (value > 1) {
        input.val(value - 1);
    }
});

// Show toast notifications
function showToast(message, type = 'success') {
    const toast = $('#toastNotification');
    const toastBody = $('.toast-body', toast);
    
    toastBody.text(message);
    toast.addClass(`bg-${type}`);
    toast.toast('show');
    
    setTimeout(function() {
        toast.toast('hide');
    }, 3000);
}

// Form validation
$('form').submit(function(e) {
    let valid = true;
    
    $(this).find('[required]').each(function() {
        if (!$(this).val()) {
            valid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        showToast('براہ کرم تمام ضروری فیلڈز بھریں', 'danger');
    }
});

// Search functionality
$('#searchForm').submit(function(e) {
    const query = $('#searchInput').val().trim();
    
    if (!query) {
        e.preventDefault();
        showToast('براہ کرم تلاش کے لیے کچھ لکھیں', 'danger');
    }
});