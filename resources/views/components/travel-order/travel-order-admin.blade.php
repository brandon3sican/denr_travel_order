@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle date range dropdown
            $('#date-range-button').on('click', function(e) {
                e.stopPropagation();
                $('#date-range-dropdown').toggleClass('hidden');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#date-range-dropdown, #date-range-button').length) {
                    $('#date-range-dropdown').addClass('hidden');
                }
            });

            // Initialize date range picker with no buttons
            const dateRangePicker = $('#date-range-picker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: '',
                    applyLabel: '',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                },
                alwaysShowCalendars: true,
                showCustomRangeLabel: true,
                autoApply: true,
                showDropdowns: true,
                showWeekNumbers: true,
                singleDatePicker: false,
                timePicker: false,
                linkedCalendars: false,
                drops: 'down',
                buttonClasses: 'hidden',
                applyButtonClasses: 'hidden',
                cancelClass: 'hidden',
                opens: 'center',
                // Remove buttons from the DOM after initialization
                callback: function() {
                    $('.daterangepicker .drp-buttons').remove();
                    $('.daterangepicker .calendar-table').on('click', function() {
                        $('.daterangepicker .drp-buttons').remove();
                    });
                },
            });

            // Handle date range selection
            dateRangePicker.on('apply.daterangepicker', function(ev, picker) {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');
                const displayText = startDate === endDate ? startDate : `${startDate} to ${endDate}`;

                $('#date-range').val(`${startDate} - ${endDate}`);
                $('#date-range-text').text(displayText);
                $('#date-range-dropdown').addClass('hidden');
                filterTravelOrders();
            });

            // Handle predefined range clicks
            $('a[data-range]').on('click', function(e) {
                e.preventDefault();
                const range = $(this).data('range');
                let startDate, endDate;
                const today = moment();

                switch (range) {
                    case 'today':
                        startDate = today.format('YYYY-MM-DD');
                        endDate = today.format('YYYY-MM-DD');
                        break;
                    case 'this-week':
                        startDate = moment().startOf('week').format('YYYY-MM-DD');
                        endDate = moment().endOf('week').format('YYYY-MM-DD');
                        break;
                    case 'next-week':
                        startDate = moment().add(1, 'weeks').startOf('week').format('YYYY-MM-DD');
                        endDate = moment().add(1, 'weeks').endOf('week').format('YYYY-MM-DD');
                        break;
                    case 'this-month':
                        startDate = moment().startOf('month').format('YYYY-MM-DD');
                        endDate = moment().endOf('month').format('YYYY-MM-DD');
                        break;
                    case 'next-month':
                        startDate = moment().add(1, 'month').startOf('month').format('YYYY-MM-DD');
                        endDate = moment().add(1, 'month').endOf('month').format('YYYY-MM-DD');
                        break;
                }

                const displayText = startDate === endDate ? startDate : `${startDate} to ${endDate}`;
                $('#date-range').val(`${startDate} - ${endDate}`);
                $('#date-range-text').text(displayText);
                $('#date-range-dropdown').addClass('hidden');
                filterTravelOrders();
            });

        });
    </script>
@endpush
