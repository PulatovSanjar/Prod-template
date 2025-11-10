@extends('admin.layouts.master')

@section('content_header')
    <div class="row">
        <div class="col-12">
            <h4>Доходы и расходы (по месяцам)</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="at-panel__body at-panel__body_no-padding">
        <div class="py-5 px-6">

            {{-- ===== Сводка ===== --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4 max-w-5xl">
                {{-- Ряд 1: номер, бренд, владелец --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                    <div>
                        <div class="text-[13px] text-gray-500">Номер карты</div>
                        <div class="text-base font-semibold" id="cardNumberText">-</div>
                    </div>
                    <div>
                        <div class="text-[13px] text-gray-500">Вид карты</div>
                        <div class="text-base font-semibold">
              <span id="cardBrandText"
                    class="inline-block rounded-full px-2.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200">
                -
              </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[13px] text-gray-500">Владелец</div>
                        <div class="text-base font-semibold" id="cardHolderText">-</div>
                    </div>
                </div>

                {{-- Ряд 2: средние поступления и расходы --}}
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5">
                        <div class="text-[11px] uppercase tracking-wide text-emerald-700">Средние доходы / год</div>
                        <div class="text-base font-bold text-emerald-900" id="avgIncomeYearText">-</div>
                    </div>
                    <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2.5">
                        <div class="text-[11px] uppercase tracking-wide text-rose-700">Средние расходы / год</div>
                        <div class="text-base font-bold text-rose-900" id="avgExpenseYearText">-</div>
                    </div>
                </div>
            </div>
            {{-- ===== /Сводка ===== --}}

            {{-- ВЫСОТА ГРАФИКА: меняй здесь (например, 20rem / 22rem / 24rem) --}}
            <div class="mt-4 h-[22rem]" style="height:22rem">
                <canvas id="chartCanvas" aria-label="График доходов и расходов" role="img"></canvas>
            </div>
        </div>
    </div>

    {{-- Скрипты --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            {{--// ==== Данные (замени на значения из контроллера через @json(...)) ====--}}
            const card = {
                numberMasked: '8600 **** **** 1234',
                brand:        'VISA',
                holder:       'John Doe'
                };

            // @json($cardNumberMasked ?? '-')
            // @json($cardBrand ?? '-')
            // @json($cardHolder ?? '-')


                {{--// const labels = @json($labels);--}}

                 const labels  = ['нояб. 2024','дек. 2024','янв. 2025','фев. 2025','мар. 2025','апр. 2025','май 2025','июн. 2025','июл. 2025','авг. 2025','сен. 2025','окт. 2025'];

                const series = {
                    income:  [24000.00, 0, 0, 57416.00, 4048.00, 0, 24000.00, 0, 11440.00, 608.00, 4168.00, 13680.00],
                    expense: [ 9600.55, 4000.00, 1600.25, 24000.00, 6400.00, 1600.00, 9600.00, 800.00, 4800.00, 1200.00, 3200.00, 7200.00]
                };
                {{--// const series = { income: @json($income), expense: @json($expense) };--}}

                // ==== Сводка ====
                const formatUSD = n => new Intl.NumberFormat('en-US', {
                    style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2
                }).format(n);

                const average12 = arr => (arr.reduce((s, x) => s + x, 0) / 12);

                document.getElementById('cardNumberText').textContent = card.numberMasked || '-';
                document.getElementById('cardBrandText').textContent  = card.brand || '-';
                document.getElementById('cardHolderText').textContent = card.holder || '-';
                document.getElementById('avgIncomeYearText').textContent  = formatUSD(average12(series.income));
                document.getElementById('avgExpenseYearText').textContent = formatUSD(average12(series.expense));

                // ==== График ====
                const mean = a => a.reduce((s,x)=>s+x,0)/Math.max(1,a.length);
                const std  = a => { const m=mean(a); return Math.sqrt(a.reduce((s,x)=>s+(x-m)*(x-m),0)/Math.max(1,a.length)); };
                const pctl = (a,q=0.9)=>{ const b=[...a].sort((x,y)=>x-y); const i=Math.min(b.length-1,Math.floor(q*b.length)); return b[i]??0; };
                const all  = [...series.income, ...series.expense];
                const suggestedMax = Math.max(pctl(all,0.9), mean(all)+2*std(all)) * 1.15;

                Chart.register(ChartDataLabels);

                const ctx = document.getElementById('chartCanvas').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Приходы',
                                data: series.income,
                                borderWidth: 2,
                                tension: 0.35,
                                pointRadius: 3,
                                borderColor: 'rgba(37,99,235,1)',
                                backgroundColor: 'rgba(37,99,235,0.08)'
                            },
                            {
                                label: 'Расходы',
                                data: series.expense,
                                borderWidth: 2,
                                tension: 0.35,
                                pointRadius: 3,
                                borderColor: 'rgba(244,63,94,1)',
                                backgroundColor: 'rgba(244,63,94,0.08)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: { padding: { top: 26, right: 12, left: 8, bottom: 12 } },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 10 } },
                            tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${formatUSD(c.parsed.y ?? 0)}` } },
                            datalabels: {
                                formatter: (v) => (v !== 0 ? formatUSD(v) : ''),
                                font: { size: 12, weight: '600' },
                                color: '#374151',
                                clip: false,
                                offset: 6,
                                align: (ctx) => {
                                    const yScale = ctx.chart.scales.y;
                                    const pt = ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.dataIndex];
                                    return (pt.y - yScale.top < 18) ? 'bottom' : 'top';
                                },
                                anchor: (ctx) => {
                                    const yScale = ctx.chart.scales.y;
                                    const pt = ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.dataIndex];
                                    return (pt.y - yScale.top < 18) ? 'start' : 'end';
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                suggestedMax,
                                grace: '15%',
                                ticks: { callback: (v) => formatUSD(v) }
                            }
                        }
                    }
                });
            });
    </script>
@endsection
