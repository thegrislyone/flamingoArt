export default function(number) {
  if (!number) return ''
  let string = number.toString()
  return string.replace(/(\d)(?=(\d{3})+(\D|$))/g, '$1 ')
}