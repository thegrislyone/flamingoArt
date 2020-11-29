export const empty = function(mixed_var) {
  return (
    mixed_var === undefined ||
    mixed_var === "" ||
    mixed_var === 0 ||
    mixed_var === "0" ||
    mixed_var === null ||
    mixed_var === false ||
    (Array.isArray(mixed_var) && mixed_var.length === 0) ||
    (typeof(mixed_var) == 'object' && Object.keys(mixed_var).length == 0)
  )
}

export const exist = function(mixed_var) {
  return (
    mixed_var != undefined &&
    mixed_var != null &&
    mixed_var != NaN
  )
}