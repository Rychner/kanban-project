import HeaderForm from '@/Components/HeaderForm';
import AppLayout from '@/Layouts/AppLayout';
import AttachmentCard from './AttachmentCard';
import MemberCard from './MemberCard';
import TaskCard from './TaskCard';
import UpdateCard from './UpdateCard';

export default function Edit({ card, page_settings, statuses, priorities, workspace }) {
    return (
        <>
            <div className="space-y-10 divide-y divide-dashed divide-gray-900/10">
                <div className="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-3">
                    <HeaderForm
                        className="col-span-full"
                        title={page_settings.title}
                        subtitle={page_settings.subtitle}
                    />
                    <UpdateCard card={card} page_settings={page_settings} statuses={statuses} priorities={priorities} />
                </div>

                <div className="grid grid-cols-1 gap-x-4 gap-y-8 pt-10 md:grid-cols-3">
                    <HeaderForm className="col-span-full" title="Members" subtitle="Please add Members to the Card" />
                    <MemberCard
                        action={route('member_card.store', {
                            card: card,
                        })}
                        members={card.members}
                    />
                </div>

                <div className="grid grid-cols-1 gap-x-4 gap-y-8 pt-10 md:grid-cols-3">
                    <HeaderForm
                        className="col-span-full"
                        title="Attachment"
                        subtitle="Please add Attachments to the Card"
                    />
                    <AttachmentCard action={route('attachment.store', [card])} attachments={card.attachments} />
                </div>

                <div className="grid grid-cols-1 gap-x-4 gap-y-8 pt-10 md:grid-cols-3">
                    <HeaderForm className="col-span-full" title="Tasks" subtitle="Please add Tasks to the Card" />
                    <TaskCard action={route('task.store', [card])} tasks={card.tasks} />
                </div>
            </div>
        </>
    );
}

Edit.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
