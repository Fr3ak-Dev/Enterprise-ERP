import type { LucideIcon } from 'lucide-react';

interface StatCardProps {
    title: string;
    value: string | number;
    icon: LucideIcon;
    iconColor: string;
    iconBgColor: string;
    trend?: {
        value: number;
        isPositive: boolean;
    };
}

const StatCard = ({ title, value, icon: Icon, iconColor, iconBgColor, trend }: StatCardProps) => {
    return (
        <div className="card">
            <div className="flex items-center justify-between">
                <div className="flex-1">
                    <p className="text-sm font-medium text-gray-600">{title}</p>
                    <p className="text-3xl font-bold text-gray-900 mt-2">{value}</p>

                    {trend && (
                        <div className="flex items-center mt-2">
                            <span className={`text-sm font-medium ${trend.isPositive ? 'text-green-600' : 'text-red-600'}`}>
                                {trend.isPositive ? '+' : '-'}{Math.abs(trend.value)}%
                            </span>
                            <span className="text-xs text-gray-500 ml-2">vs mes anterior</span>
                        </div>
                    )}
                </div>

                <div className={`${iconBgColor} rounded-full p-4`}>
                    <Icon className={`w-8 h-8 ${iconColor}`} />
                </div>
            </div>
        </div>
    );
};

export default StatCard;