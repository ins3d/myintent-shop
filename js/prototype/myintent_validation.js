// customization - only allow letters, numbers, "&", and spaces
Validation.add('validate-myintent-chars', 'Please use uppercase characters, numbers, or "&"', function(v) {
    return Validation.get('IsEmpty').test(v) || /^[a-zA-Z0-9&\x20]+$/.test(v);
});
