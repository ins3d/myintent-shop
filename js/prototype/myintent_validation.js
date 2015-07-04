// customization - only allow letters, numbers, "&", and spaces
Validation.add('validate-myintent-chars', 'Only use the following characters: ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890&', function(v) {
    return Validation.get('IsEmpty').test(v) || /^[a-zA-Z0-9&\x20]+$/.test(v);
});
