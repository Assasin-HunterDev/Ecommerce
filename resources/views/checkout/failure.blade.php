<x-app-layout>
    <div class="w-[400px] mx-auto bg-red-500 py-4 px-6 text-white rounded-lg shadow-md">
        <div class="text-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="100"
                height="100"
                viewBox="0 0 32 32"
                class="mx-auto"
            >
                <path
                    d="M178.584 455.6a1 1 0 0 0-.704.288 1 1 0 0 0 0 1.416l.704.704-.704.704a1 1 0 0 0 0 1.416 1 1 0 0 0 .816.288 1 1 0 0 0 .592-.288l.704-.704.704.704a1 1 0 0 0 .592.288 1 1 0 0 0 .816-.288 1 1 0 0 0 0-1.416l-.704-.704.704-.704a1 1 0 0 0 0-1.416 1 1 0 0 0-.704-.288 1 1 0 0 0-.192.024 1 1 0 0 0-.512.272l-.704.704-.704-.704a1 1 0 0 0-.704-.288zM170 453.016a1 1 0 0 0-1 1 1 1 0 0 0 1 1h3a1 1 0 0 0 1-1 1 1 0 0 0-1-1zm-7-3a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1zm1 2h2v2h-2z"
                    style="fill:#FFFFFF;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none" transform="translate(-156 -436)"
                />
                <path
                    d="M160 441.016a2.024 2.024 0 0 0-2 2v15c0 1.088.912 2 2 2h14.344a6.015 6.015 0 0 0 5.656 4c3.304 0 6-2.696 6-6a5.992 5.992 0 0 0-2-4.464v-10.536a2.024 2.024 0 0 0-2-2zm0 2h22v2h-22zm0 4h22v5.344a5.928 5.928 0 0 0-2-.344c-3.2 0-5.832 2.528-5.992 5.688a6.312 6.312 0 0 0-.008.312h-14zm10 3a1 1 0 0 0-1 1 1 1 0 0 0 1 1h5a1 1 0 0 0 1-1 1 1 0 0 0-1-1zm10 4c2.224 0 4 1.776 4 4s-1.776 4-4 4a3.984 3.984 0 0 1-3.896-3.072 1 1 0 0 0-.048-.264l-.008-.056-.024-.2a4.08 4.08 0 0 1 .056-1.216 3.984 3.984 0 0 1 3.92-3.192z"
                    style="fill:#FFFFFF;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none" transform="translate(-156 -436)"
                />
            </svg>
            <h2 class="text-xl font-semibold mb-2">Payment Not Successful</h2>
            <p class="text-sm">{{$message ?? ''}}</p>
        </div>
    </div>
</x-app-layout>
