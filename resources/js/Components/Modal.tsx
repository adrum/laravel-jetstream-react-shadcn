import React, { PropsWithChildren } from 'react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogTitle,
} from '@/Components/ui/dialog';
import { cn } from '@/lib/utils';

export interface ModalProps {
  isOpen: boolean;
  onClose(): void;
  maxWidth?: string;
  /**
   * Radix requires an accessible name and description on every dialog. The
   * visible heading lives inside the modal's own content, so this is rendered
   * for assistive technology only.
   */
  title?: string;
  description?: string;
}

export default function Modal({
  isOpen,
  onClose,
  maxWidth = '2xl',
  title,
  description,
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
        <DialogTitle className="sr-only">{title ?? 'Dialog'}</DialogTitle>
        <DialogDescription className="sr-only">
          {description ?? title ?? 'Dialog content'}
        </DialogDescription>

        {children}
      </DialogContent>
    </Dialog>
  );
}
