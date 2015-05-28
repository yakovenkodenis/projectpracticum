
using System.Windows;

namespace Autoschool
{
    public static class DataGridTextSearch
    {
        public static readonly DependencyProperty SearchValueProperty =
            DependencyProperty.RegisterAttached("SearchValue", typeof (string), typeof (DataGridTextSearch),
                new FrameworkPropertyMetadata(string.Empty, FrameworkPropertyMetadataOptions.Inherits));

        public static string GetSearchValue(DependencyObject obj)
        {
            return (string) obj.GetValue(SearchValueProperty);
        }

        public static void SetSearchValue(DependencyObject obj, string value)
        {
            obj.SetValue(SearchValueProperty, value);
        }

        public static readonly DependencyProperty IsTextMatchProperty =
            DependencyProperty.RegisterAttached("IsTextMatch", typeof (bool), typeof (DataGridTextSearch),
                new UIPropertyMetadata(false));

        public static bool GetIsTextMatch(DependencyObject obj)
        {
            return (bool) obj.GetValue(IsTextMatchProperty);
        }

        public static void SetIsTextMatch(DependencyObject obj, bool value)
        {
            obj.SetValue(IsTextMatchProperty, value);
        }
    }
}
