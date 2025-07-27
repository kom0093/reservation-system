import ConfirmModal from '@/components/modals/ConfirmModal.vue';
import { useModal, UseModalReturnType } from 'vue-final-modal';

export function useConfirmModal(confirmFn: (args: any) => void, title = 'Potvrdit akci', text = 'Opravdu chcete tento záznam smazat?', component = ConfirmModal): {confirmModal: UseModalReturnType<any>, open: () => void} {
    const confirmModal = useModal({
        component,
        attrs: {
            title,
            text,
            onConfirm: (args: any) => {
                close();
                if (confirmFn) {
                    confirmFn(args);
                }
            },
            onCancel: () => close(),
        }
    });

    const open = (): void => {
        void confirmModal.open();
    }

    const close = (): void => {
        void confirmModal.close();
    }

    return {
        confirmModal,
        open,
    }
}
