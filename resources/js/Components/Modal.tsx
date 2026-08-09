import React, { PropsWithChildren } from 'react';
import { Dialog, DialogContent } from '@/Components/ui/dialog';
import { cn } from '@/lib/utils';

export interface ModalProps {
  isOpen: boolean;
  onClose(): void;
  maxWidth?: string;
}

export default function Modal({
  isOpen,
  onClose,
  maxWidth = '2xl',
  children,
}: PropsWithChildren<ModalProps>) {
  const maxWidthClass = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
  }[maxWidth];

  return (
    <Dialog open={isOpen} onOpenChange={open => !open && onClose()}>
      <DialogContent className={cn('gap-0 p-0', maxWidthClass)}>
        {children}
      </DialogContent>
    </Dialog>
  );
}
