@push('styles')
    <style>
        /* Signature Canvas */
        #signature-canvas {
            width: 100%;
            height: 100%;
            touch-action: none;
            background-color: white;
        }

        /* Upload Container */
        #upload-container {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 200px;
        }

        #upload-container.drag-over {
            background-color: #f8fafc;
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1);
        }

        /* Signature Preview */
        #signature-preview {
            max-height: 180px;
            max-width: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .grid-cols-1 {
                grid-template-columns: 1fr;
            }

            .md\:grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .md\:flex-row {
                flex-direction: column;
            }

            .md\:ml-6 {
                margin-left: 0;
                margin-top: 1rem;
            }
        }
    </style>
@endpush
