function toggleVisibility(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.name = 'eye-off-outline';
    } else {
        field.type = 'password';
        icon.name = 'eye-outline';
    }
}