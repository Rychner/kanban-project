import { SortableContext, useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Link } from '@inertiajs/react';
import { useMemo } from 'react';
import { PiPlus } from 'react-icons/pi';
import CardList from './CardList';

export default function ({ status, cards, workspace, handleDeleteCard }) {
    const cardsIds = useMemo(() => {
        return cards.map((card) => card.id);
    }, [cards]);

    const { setNodeRef, attributes, listeners, transform, transition, isDragging } = useSortable({
        id: status.value,
        data: {
            type: 'Status',
            status,
        },
    });

    const style = {
        transition,
        transform: CSS.Transform.toString(transform),
    };

    const statusColors = {
        'To Do': 'bg-red-500 text-white',
        'In Progress': 'bg-blue-500 text-white',
        'On Review': 'bg-yellow-500 text-white',
        Done: 'bg-green-500 text-white',
    };

    return (
        <div className="w-full space-y-4 rounded shadow-md sm:w-1/4" ref={setNodeRef} style={style}>
            <div
                {...attributes}
                {...listeners}
                className={`flex items-center justify-between rounded-t px-2 py-1 ${
                    statusColors[status.value] || 'bg-gray-100 text-gray-600'
                }`}
            >
                <span className="text-base font-semibold leading-relaxed tracking-tighter">{status.value}</span>
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
            <div className="flex flex-grow flex-col gap-4 overflow-y-auto overflow-x-hidden p-4">
                <SortableContext items={cardsIds}>
                    {cards.map((card) => (
                        <CardList key={card.id} card={card} workspace={workspace} handleDeleteCard={handleDeleteCard} />
                    ))}
                </SortableContext>
            </div>
        </div>
    );
}
