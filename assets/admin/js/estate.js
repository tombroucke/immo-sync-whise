jQuery(document).ready(function ($) {
    $(document).on('click', '.clickable-eye', function () {
        var icon = $(this);
        var metaKey = icon.data('meta-key');
        var metaValue = icon.data('meta-value');
        var newValue = (metaValue === '1') ? '0' : '1';

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'toggle_show_field',
                post_id: estateData.post_id,
                meta_key: metaKey,
                meta_value: newValue
            },
            success: function (response) {
                if (response === 'success') {
                    // Toggle the meta value in the HTML element
                    icon.data('meta-value', newValue);

                    if (newValue === '0') {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                } else {
                    alert('Failed to update the value. Please try again.');
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
