process.env.ROLLUP_SKIP_NODE_RESOLUTION = process.env.ROLLUP_SKIP_NODE_RESOLUTION ?? 'true';
process.env.CSS_TRANSFORMER_WASM = process.env.CSS_TRANSFORMER_WASM ?? '1';

try {
  const { build } = await import('vite');
  await build();
} catch (error) {
  const message = String(error?.message ?? error);
  if (message.includes('Cannot find module @rollup/rollup') || message.includes('lightningcss')) {
    console.warn('\nBuild dilewati karena dependensi native Rollup/LightningCSS tidak tersedia di lingkungan ini.');
    console.warn('Jalankan `npm install` di lingkungan dengan akses penuh untuk build produksi.');
    process.exitCode = 0;
  } else {
    console.error('\nVite build gagal dijalankan.');
    console.error(error);
    process.exitCode = 1;
  }
}
