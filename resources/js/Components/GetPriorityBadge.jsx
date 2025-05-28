import { Badge } from '@/Components/ui/badge';
import { PRIORITY } from '@/lib/utils';

export default function GetPriorityBadge({ priority }) {
    const { URGENT, HIGH, MEDIUM, LOW, UNKNOWN } = PRIORITY;
    let badge, text;

    switch (priority) {
        case URGENT:
            badge = 'bg-red-500 hover:bg-red-400';
            text = URGENT;
            break;
        case HIGH:
            badge = 'bg-yellow-500 hover:bg-yellow-400';
            text = HIGH;
            break;
        case MEDIUM:
            badge = 'bg-blue-500 hover:bg-blue-400';
            text = MEDIUM;
            break;
        case LOW:
            badge = 'bg-green-500 hover:bg-green-400';
            text = LOW;
            break;

        default:
            badge = '';
            text = UNKNOWN;
            break;
    }

    return <Badge className={badge}>{text}</Badge>;
}
