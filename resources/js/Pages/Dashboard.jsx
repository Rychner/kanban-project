import AppLayout from '@/Layouts/AppLayout';

export default function Dashboard() {
    return <div>This is Dashboard</div>;
}

Dashboard.layout = (page) => <AppLayout children={page} title="Dashboard" />;
