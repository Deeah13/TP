import { h, markRaw } from 'vue';

export function createIcon(...paths) {
  return markRaw({
    name: 'InlineIcon',
    render() {
      return h(
        'svg',
        {
          viewBox: '0 0 24 24',
          'aria-hidden': 'true',
        },
        paths.map((attrs) => h('path', attrs)),
      );
    },
  });
}

export function createStrokeIcon({
  d,
  stroke = 'currentColor',
  strokeWidth = 1.5,
  strokeLinecap = 'round',
  strokeLinejoin = 'round',
  fill = 'none',
} = {}) {
  return createIcon({
    d,
    stroke,
    fill,
    'stroke-width': strokeWidth,
    'stroke-linecap': strokeLinecap,
    'stroke-linejoin': strokeLinejoin,
  });
}
