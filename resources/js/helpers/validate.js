function isEmpty(value) {
    if (value === null || value === undefined) return true

    if (typeof value === 'string') {
        return value.trim() === ''
    }

    if (Array.isArray(value)) {
        return value.length === 0
    }

    return false
}

export function validateFieldsWithErrors(obj, fields = []) {
    const errors = {}

    fields.forEach(field => {
        const value = obj[field]

        if (isEmpty(value)) {
            errors[field] = 'Campo obrigatório, favor, preencher!'
        }
    })

    return {
        isValid: Object.keys(errors).length === 0,
        errors
    }
}
