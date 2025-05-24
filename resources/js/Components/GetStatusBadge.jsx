import { STATUS } from '@/lib/utils';
import { Badge } from '@/resources/js/Components/ui/badge';

export default function GetStatusBadge({ status }) {
    const { TODO, INPROGRESS, ONREVIEW, DONE, UNKNOWN } = STATUS;
    let badge, text;

    switch (status) {
        case TODO:
            badge = 'bg-red-500 hover:bg-red-400';
            text = TODO;
            break;
        case INPROGRESS:
            badge = 'bg-yellow-500 hover:bg-yellow-400';
            text = INPROGRESS;
            break;
        case ONREVIEW:
            badge = 'bg-blue-500 hover:bg-blue-400';
            text = ONREVIEW;
            break;
        case DONE:
            badge = 'bg-green-500 hover:bg-green-400';
            text = DONE;
            break;

        default:
            badge = '';
            text = UNKNOWN;
            break;
    }

    return <Badge className={badge}>{text}</Badge>;
}
