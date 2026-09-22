export function withTransition(callback) {
  if (typeof document.startViewTransition === "function") {
    return document.startViewTransition(callback);
  }
  return callback();
}
