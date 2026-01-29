import {Chart} from 'chart.js/auto'

const rootStyles = getComputedStyle(document.documentElement);
const fontSans = rootStyles.getPropertyValue('--font-sans').trim();
Chart.defaults.font.family = fontSans || 'Satoshi-Variable, ui-sans-serif, system-ui, sans-serif';

const softSkillBarLabels = {
    id: 'softSkillBarLabels',
    afterDatasetsDraw(chart, _args, pluginOptions) {
        const options = pluginOptions || {};
        const labelColor = options.labelColor || '#8181a5';
        const valueColor = options.valueColor || '#1c1d21';
        const fontSize = options.fontSize || 10;
        const valueFontSize = options.valueFontSize || 11;
        const {ctx, chartArea} = chart;
        const meta = chart.getDatasetMeta(0);
        const labels = chart.data.labels || [];
        const values = chart.data.datasets?.[0]?.data || [];

        ctx.save();
        meta.data.forEach((bar, index) => {
            const label = labels[index] ?? '';
            const value = values[index] ?? '';

            if (label === '' && value === '') {
                return;
            }

            const x = chartArea.left;
            const topY = bar.y - (bar.height / 2) - 4;
            const bottomY = bar.y + (bar.height / 2) + 12;

            ctx.textAlign = 'left';
            ctx.textBaseline = 'bottom';
            ctx.font = `500 ${fontSize}px ${Chart.defaults.font.family}`;
            ctx.fillStyle = labelColor;
            ctx.fillText(String(label), x, topY);

            ctx.textBaseline = 'top';
            ctx.font = `600 ${valueFontSize}px ${Chart.defaults.font.family}`;
            ctx.fillStyle = valueColor;
            ctx.fillText(String(value), x, bottomY);
        });
        ctx.restore();
    },
};

Chart.register(softSkillBarLabels);
window.Chart = Chart
