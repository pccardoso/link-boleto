
export const validateDocument = (type, value) => {

  if (!value) return false

  // ========================
  // PLATE
  // ========================
  if (type === "plate") {
    const plate = value.toUpperCase().replace(/[^A-Z0-9]/g, '')

    const oldPlate = /^[A-Z]{3}[0-9]{4}$/
    const mercosulPlate = /^[A-Z]{3}[0-9][A-Z][0-9]{2}$/

    return oldPlate.test(plate) || mercosulPlate.test(plate)
  }

  // ========================
  // CPF / CNPJ
  // ========================
  if (type === "cpf") {

    const doc = value.replace(/\D/g, '')

    // CPF
    if (doc.length === 11) {

      if (/^(\d)\1+$/.test(doc)) return false

      let sum = 0
      for (let i = 0; i < 9; i++) {
        sum += doc[i] * (10 - i)
      }

      let firstDigit = (sum * 10) % 11
      if (firstDigit === 10) firstDigit = 0
      if (firstDigit != doc[9]) return false

      sum = 0
      for (let i = 0; i < 10; i++) {
        sum += doc[i] * (11 - i)
      }

      let secondDigit = (sum * 10) % 11
      if (secondDigit === 10) secondDigit = 0

      return secondDigit == doc[10]
    }

    // CNPJ
    if (doc.length === 14) {

      let length = doc.length - 2
      let numbers = doc.substring(0, length)
      let digits = doc.substring(length)

      let sum = 0
      let pos = length - 7

      for (let i = length; i >= 1; i--) {
        sum += numbers[length - i] * pos--
        if (pos < 2) pos = 9
      }

      let result = sum % 11 < 2 ? 0 : 11 - sum % 11
      if (result != digits[0]) return false

      length++
      numbers = doc.substring(0, length)
      sum = 0
      pos = length - 7

      for (let i = length; i >= 1; i--) {
        sum += numbers[length - i] * pos--
        if (pos < 2) pos = 9
      }

      result = sum % 11 < 2 ? 0 : 11 - sum % 11

      return result == digits[1]
    }

    return false
  }

  return false
}