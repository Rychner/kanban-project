import { ActionDialog } from '@/Components/ActionDialog';
import GetPriorityBadge from '@/Components/GetPriorityBadge';
import { Card, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, router } from '@inertiajs/react';
import { PiDotsThreeOutlineFill, PiPencilSimpleFill, PiPlus, PiTrashSimpleFill } from 'react-icons/pi';
import { toast } from 'sonner';

export default function Show({ ...props }) {
    const workspace = props.workspace;
    const statuses = props.statuses;
    const cards = props.card;

    const statusColors = {
        'To Do': 'bg-red-500 text-white',
        'In Progress': 'bg-blue-500 text-white',
        'On Review': 'bg-yellow-500 text-white',
        Done: 'bg-green-500 text-white',
    };

    return (
        <>
            <div>
                <img src={workspace.cover} alt={workspace.logo} className="h-32 w-full object-cover lg:h-48" />
            </div>
            <div className="px-2 sm:px-4">
                <div className="-mt-12 sm:flex sm:items-center sm:space-x-5">
                    <div className="flex">
                        <img
                            src={workspace.logo}
                            alt={workspace.logo}
                            className="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32"
                        />
                    </div>
                    <div className="items-center sm:flex sm:min-w-0 sm:flex-1 sm:justify-end sm:space-x-6 sm:pb-1">
                        <div className="mt-6 min-w-0 flex-1">
                            <CardTitle className="text-4xl leading-relaxed tracking-tighter">
                                {workspace.name}
                            </CardTitle>
                        </div>
                        <div className="mt-8 flex items-center gap-x-8">
                            <Link
                                href={route('card.create', [workspace])}
                                className="inline-flex justify-center whitespace-nowrap rounded-md text-sm font-medium text-foreground ring-offset-background transition-colors hover:font-bold hover:text-red-500 hover:no-underline hover:transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                            >
                                Create Card
                            </Link>
                            <Link
                                href={route('workspace.edit', [workspace])}
                                className="inline-flex justify-center whitespace-nowrap rounded-md text-sm font-medium text-foreground ring-offset-background transition-colors hover:font-bold hover:text-red-500 hover:no-underline hover:transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                            >
                                Setting
                            </Link>
                        </div>
                    </div>
                </div>
                {/* card */}
                <div className="mt-8 flex w-full flex-col justify-start gap-x-5 gap-y-8 sm:flex-row">
                    {statuses.map((status, index) => (
                        <div className="w-full space-y-4 rounded shadow-md sm:w-1/4" key={index}>
                            <div
                                className={`flex items-center justify-between rounded-t px-2 py-1 ${
                                    statusColors[status.value] || 'bg-gray-100 text-gray-600'
                                }`}
                            >
                                <span className="text-base font-semibold leading-relaxed tracking-tighter">
                                    {status.value}
                                </span>
                                <div className="flex items-center gap-x-3">
                                    <Link
                                        href={route('card.create', {
                                            workspace: workspace,
                                            _query: {
                                                status: status.value,
                                            },
                                        })}
                                    >
                                        <PiPlus className="hover:text-bold h-4 w-4 text-white transition-colors duration-200 hover:text-foreground"></PiPlus>
                                    </Link>
                                </div>
                            </div>
                            {/* column card container*/}
                            <div className="flex flex-grow flex-col gap-4 overflow-y-auto overflow-x-hidden p-2">
                                {cards
                                    .filter((card) => card.status === status.value)
                                    .map((card, index) => (
                                        <Card
                                            key={index}
                                            className="relative rounded-xl hover:ring-2 hover:ring-inset hover:ring-red-500"
                                        >
                                            <CardHeader>
                                                <div className="flex items-center justify-between">
                                                    <CardTitle className="line-clamp-2 text-base leading-relaxed tracking-tighter">
                                                        <Link
                                                            href={route('card.show', [workspace, card])}
                                                            className="hover:text-red-500"
                                                        >
                                                            {card.title}
                                                        </Link>
                                                    </CardTitle>
                                                    <DropdownMenu>
                                                        <DropdownMenuTrigger>
                                                            <PiDotsThreeOutlineFill className="size-4" />
                                                        </DropdownMenuTrigger>
                                                        <DropdownMenuContent align="end" className="w-16">
                                                            <DropdownMenuItem asChild>
                                                                <Link href={route('card.edit', [workspace, card])}>
                                                                    <div className="flex items-center justify-between">
                                                                        <span>Edit</span>
                                                                        <span className="size-4">
                                                                            <PiPencilSimpleFill />
                                                                        </span>
                                                                    </div>
                                                                </Link>
                                                            </DropdownMenuItem>
                                                            <DropdownMenuGroup>
                                                                <ActionDialog
                                                                    trigger={
                                                                        <DropdownMenuItem
                                                                            onSelect={(e) => e.preventDefault()}
                                                                        >
                                                                            <div className="flex items-center justify-between">
                                                                                <span>Delete</span>
                                                                                <span className="size-4">
                                                                                    <PiTrashSimpleFill />
                                                                                </span>
                                                                            </div>
                                                                        </DropdownMenuItem>
                                                                    }
                                                                    title="Delete Card"
                                                                    description="Are You Sure Want to Delete This Card ?"
                                                                    action={() =>
                                                                        router.delete(
                                                                            route('card.destroy', [workspace, card]),
                                                                            {
                                                                                preserveScroll: true,
                                                                                preserveState: true,
                                                                                onSuccess: (success) => {
                                                                                    const flash = flashMessage(success);
                                                                                    if (flash)
                                                                                        toast[flash.type](
                                                                                            flash.message,
                                                                                        );
                                                                                },
                                                                            },
                                                                        )
                                                                    }
                                                                />
                                                            </DropdownMenuGroup>
                                                        </DropdownMenuContent>
                                                    </DropdownMenu>
                                                </div>
                                                <div>
                                                    <GetPriorityBadge priority={card.priority} />
                                                </div>
                                                <CardDescription className="line-clamp-4 leading-relaxed tracking-tighter">
                                                    {card.description}
                                                </CardDescription>
                                            </CardHeader>
                                        </Card>
                                    ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}

Show.layout = (page) => <AppLayout children={page} title={page.props.workspace.name} />;
