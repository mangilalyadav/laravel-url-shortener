<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Generated Short URLs</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            margin-bottom: 5px;
        }

        .filter {
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f1f1;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        .text-center {
            text-align: center;
        }

        .url {
            word-break: break-all;
        }

    </style>

</head>

<body>

    <h2>
        Generated Short URLs
    </h2>

    <div class="filter">
        Filter: <strong>{{ $filterName }}</strong>
        <br>
        Generated on: {{ now()->format('d M Y, h:i A') }}
    </div>


    <table>

        <thead>

            <tr>

                <th width="4%">
                    #
                </th>

                <th width="22%">
                    Short URL
                </th>

                <th width="30%">
                    Long URL
                </th>

                <th width="15%">
                    Created By
                </th>

                <th width="15%">
                    Company
                </th>

                <th width="7%">
                    Hits
                </th>

                <th width="12%">
                    Created At
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($urls as $url)

                <tr>

                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="url">
                        {{ route('short-url.resolve', $url->code) }}
                    </td>

                    <td class="url">
                        {{ $url->long_url }}
                    </td>

                    <td>
                        {{ $url->creator->name ?? '-' }}
                    </td>

                    <td>
                        {{ $url->client->name ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ number_format($url->hits) }}
                    </td>

                    <td>
                        {{ $url->created_at->format('d M Y, h:i A') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        No generated URLs found for
                        {{ $filterName }}.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>