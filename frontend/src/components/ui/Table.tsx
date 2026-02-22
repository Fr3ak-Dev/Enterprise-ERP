/**
 * Tabla reutilizable con soporte para loading, empty state y acciones.
 */

interface Column<T> {
    key: string;
    label: string;
    render?: (item: T) => React.ReactNode;
    className?: string;
}

interface TableProps<T> {
    data: T[];
    columns: Column<T>[];
    isLoading?: boolean;
    emptyMessage?: string;
    onRowClick?: (item: T) => void;
}

function Table<T extends { id: number | string }>({
    data,
    columns,
    isLoading = false,
    emptyMessage = 'No hay datos para mostrar',
    onRowClick,
}: TableProps<T>) {
    if (isLoading) {
        return (
            <div className="bg-white rounded-lg shadow overflow-hidden">
                <div className="p-8 text-center">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
                    <p className="mt-4 text-gray-600">Cargando...</p>
                </div>
            </div>
        );
    }

    if (data.length === 0) {
        return (
            <div className="bg-white rounded-lg shadow overflow-hidden">
                <div className="p-8 text-center">
                    <p className="text-gray-600">{emptyMessage}</p>
                </div>
            </div>
        );
    }

    return (
        <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                    {/* Header */}
                    <thead className="bg-gray-50">
                        <tr>
                            {columns.map((column) => (
                                <th
                                    key={column.key}
                                    className={`
                                        px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider
                                        ${column.className || ''}
                                    `}
                                >
                                    {column.label}
                                </th>
                            ))}
                        </tr>
                    </thead>

                    {/* Body */}
                    <tbody className="bg-white divide-y divide-gray-200">
                        {data.map((item) => (
                            <tr
                                key={item.id}
                                onClick={() => onRowClick?.(item)}  // optional chaining
                                className={`
                                    ${onRowClick ? 'cursor-pointer hover:bg-gray-50' : ''}
                                    transition-colors
                                `}
                            >
                                {columns.map((column) => (
                                    <td
                                        key={column.key}
                                        className={`px-6 py-4 whitespace-nowrap ${column.className || ''}`}
                                    >
                                        {column.render
                                            ? column.render(item)
                                            : (item as any)[column.key]}
                                    </td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

export default Table;