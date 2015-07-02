// customization - only allow uppercase chars, numbers, and &
Validation.add('validate-myintent-chars', 'Please use uppercase characters, numbers, or "&"', function(v) {
    return Validation.get('IsEmpty').test(v) || /^[A-Z0-9&\x20]+$/.test(v)
})