import { ref, computed } from "vue";

export default function usePulseLoader() {
  const loading = ref(true);
  const color = ref("#5dc596");
  const size = ref("15px");
  const margin = ref("2px");
  const radius = ref("100%");

  const spinnerStyle = computed(() => ({
    backgroundColor: color.value,
    width: size.value,
    height: size.value,
    margin: margin.value,
    borderRadius: radius.value,
    display: "inline-block",
    animationName: "v-pulseStretchDelay",
    animationDuration: "0.75s",
    animationIterationCount: "infinite",
    animationTimingFunction: "cubic-bezier(.2,.68,.18,1.08)",
    animationFillMode: "both",
  }));

  const spinnerDelay1 = { animationDelay: "0.12s" };
  const spinnerDelay2 = { animationDelay: "0.24s" };
  const spinnerDelay3 = { animationDelay: "0.36s" };

  return {
    loading,
    spinnerStyle,
    spinnerDelay1,
    spinnerDelay2,
    spinnerDelay3,
  };
}
