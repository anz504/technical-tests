function magic_inc(value, direction) {
  if (typeof value !== 'number' || !isFinite(value) || value === 0) {
    return 0;
  }

  const sign = Math.sign(value);
  let abs = Math.abs(value);

  const exp = Math.floor(Math.log10(abs));
  const step = Math.pow(10, exp);

  const normalized = Math.ceil(abs / step) * step;

  const signNormalized = () => sign * normalized;

  if (normalized !== abs) {
    return signNormalized();
  }

  if (direction === 'inc') {
    return signNormalized() + sign * step;
  }

  if (direction === 'dec') {
    const res = abs - step;
    return res <= 0 ? 0 : sign * res;
  }

  return 0;
}

    console.log("Rango 0-1:");
    console.log("magic_inc(0.5, 'dec') =>", magic_inc(0.5, 'dec')); // esperado: 0.4
    console.log("magic_inc(0.1, 'dec') =>", magic_inc(0.1, 'dec')); // esperado: 0.09

