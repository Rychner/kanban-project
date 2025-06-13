import { ActionDialog } from '@/Components/ActionDialog';
import GetPriorityBadge from '@/Components/GetPriorityBadge';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Progress } from '@/Components/ui/progress';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Link } from '@inertiajs/react';
import {
    PiCheckSquare,
    PiDotsThreeOutlineFill,
    PiLinkSimple,
    PiPencilSimpleFill,
    PiTrashSimpleFill,
    PiUser,
} from 'react-icons/pi';

export default function CardList({ card, workspace, handleDeleteCard }) {
    const { setNodeRef, attributes, listeners, transform, transition, isDragging } = useSortable({
        id: card.id,
        data: {
            type: 'Card',
            card,
        },
    });

    const style = {
        transition,
        transform: CSS.Transform.toString(transform),
    };

    if (isDragging) {
        return (
            <Card
                ref={setNodeRef}
                style={style}
                className="relative flex h-[100px] min-h-[100px] cursor-grab items-center rounded-xl border border-dashed border-muted-foreground p-2.5 text-left opacity-30"
            ></Card>
        );
    }

    return (
        <Card
            ref={setNodeRef}
            style={style}
            {...attributes}
            {...listeners}
            className="task hover:ring-red:500 relative cursor-grab rounded-xl hover:ring-2 hover:ring-inset"
        >
            <CardHeader>
                <div className="flex items-center justify-between">
                    <CardTitle className="line-clamp-2 text-base leading-relaxed tracking-tighter">
                        <Link href={route('card.show', [workspace, card])} className="hover:text-red-500">
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
                                        <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
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
                                    action={() => handleDeleteCard(card.id)}
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
            <CardContent>
                <div className="flex flex-col space-y-4">
                    {card.has_task && (
                        <div>
                            <div className="mb-1.5 flex items-center justify-between">
                                <span className="text-sm leading-relaxed tracking-tighter text-muted-foreground">
                                    {card.deadline > 0 ? (
                                        <span className="text-green-500">
                                            <b className="text-muted-foreground">{card.deadline}</b> days left
                                        </span>
                                    ) : card.deadline == 0 ? (
                                        <span className="text-yellow-500">Today is The Deadline</span>
                                    ) : (
                                        <span className="text-red-500">Overdue</span>
                                    )}
                                </span>
                                <span className="text-sm leading-relaxed tracking-tighter text-muted-foreground">
                                    <span>
                                        <b>{card.percentage}</b> of 100
                                    </span>
                                </span>
                            </div>
                            <Progress className="mb-4 h-2.5 w-full" value={card.percentage} />
                        </div>
                    )}
                    <div className="flex items-center justify-between gap-x-1">
                        {card.has_task && (
                            <div className="flex items-center gap-x-1">
                                <PiCheckSquare className="h-4 w-4 text-blue-500" />
                                <span className="text-sm leading-relaxed tracking-tighter text-muted-foreground">
                                    <b>{card.tasks_count}</b> Tasks
                                </span>
                            </div>
                        )}
                        {card.has_attachment && (
                            <div className="flex items-center gap-x-1">
                                <PiLinkSimple className="h-4 w-4 text-green-500" />
                                <span className="text-sm leading-relaxed tracking-tighter text-muted-foreground">
                                    <b>{card.attachments_count}</b> Files
                                </span>
                            </div>
                        )}
                    </div>
                    <div className="flex items-center justify-between gap-x-4">
                        {card.member_count > 1 && (
                            <div className="flex items-center gap-x-1">
                                <PiUser className="h-4 w-4 text-muted-foreground" />
                                <span className="text-sm leading-relaxed tracking-tighter text-muted-foreground">
                                    <b>{card.member_count}</b> Members
                                </span>
                            </div>
                        )}
                    </div>
                </div>
                <div className="flex flex-col space-y-4">
                    <div className="flex">
                        {card.members.map((member, index) => (
                            <span key={index} className={index !== 0 ? '-ml-3' : ''}>
                                <Avatar>
                                    <AvatarImage src={member.user.avatar} />
                                    <AvatarFallback>{member.user.name.substring(0, 1)}</AvatarFallback>
                                </Avatar>
                            </span>
                        ))}
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
