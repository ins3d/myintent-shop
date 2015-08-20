// customization - only allow letters, numbers, "&", and spaces
Validation.add('validate-myintent-chars', 'Use only A-Z capital letters, 0-9, and "&"', function(v) {
    return Validation.get('IsEmpty').test(v) || /^[A-Z0-9&\x20]+$/.test(v);
});
